<?php
// Include database configuration
require_once 'db_config.php';

// Set response header to JSON
header('Content-Type: application/json');

// Get all students with their lesson count
function getStudents() {
    global $conn;
    
    try {
        // Query to get students with their lesson count
        $query = "
            SELECT 
                s.*, 
                (SELECT COUNT(*) FROM lessons WHERE student_id = s.id) as lesson_count
            FROM 
                students s
            ORDER BY 
                s.last_name, s.first_name
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll();
        
        echo json_encode([
            'status' => 'success',
            'data' => $students
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch students: ' . $e->getMessage()
        ]);
    }
}

// Get a specific student with all their lessons
function getStudent($id) {
    global $conn;
    
    try {
        // Get student details
        $studentQuery = "SELECT * FROM students WHERE id = :id";
        $studentStmt = $conn->prepare($studentQuery);
        $studentStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $studentStmt->execute();
        $student = $studentStmt->fetch();
        
        if (!$student) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Student not found'
            ]);
            return;
        }
        
        // Get student lessons
        $lessonsQuery = "SELECT * FROM lessons WHERE student_id = :student_id ORDER BY lesson_date";
        $lessonsStmt = $conn->prepare($lessonsQuery);
        $lessonsStmt->bindParam(':student_id', $id, PDO::PARAM_INT);
        $lessonsStmt->execute();
        $lessons = $lessonsStmt->fetchAll();
        
        // Add lessons to student data
        $student['lessons'] = $lessons;
        
        echo json_encode([
            'status' => 'success',
            'data' => $student
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch student details: ' . $e->getMessage()
        ]);
    }
}

// Add a new student
function addStudent() {
    global $conn;
    
    // Get input data as JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['firstName']) || !isset($input['lastName']) || 
        !isset($input['age']) || !isset($input['phone']) || 
        !isset($input['registrationDate'])) {
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields'
        ]);
        return;
    }
    
    try {
        $query = "
            INSERT INTO students 
                (first_name, last_name, age, phone, email, registration_date) 
            VALUES 
                (:first_name, :last_name, :age, :phone, :email, :registration_date)
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':first_name', $input['firstName']);
        $stmt->bindParam(':last_name', $input['lastName']);
        $stmt->bindParam(':age', $input['age'], PDO::PARAM_INT);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':registration_date', $input['registrationDate']);
        
        $stmt->execute();
        $newId = $conn->lastInsertId();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Student added successfully',
            'id' => $newId
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add student: ' . $e->getMessage()
        ]);
    }
}

// Add a new lesson
function addLesson() {
    global $conn;
    
    // Get input data as JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['studentId']) || !isset($input['lessonDate'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields'
        ]);
        return;
    }
    
    try {
        // First check if student exists
        $checkQuery = "SELECT id FROM students WHERE id = :student_id";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':student_id', $input['studentId'], PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Student not found'
            ]);
            return;
        }
        
        // Check if we already have 20 lessons for this student
        $countQuery = "SELECT COUNT(*) as lesson_count FROM lessons WHERE student_id = :student_id";
        $countStmt = $conn->prepare($countQuery);
        $countStmt->bindParam(':student_id', $input['studentId'], PDO::PARAM_INT);
        $countStmt->execute();
        $result = $countStmt->fetch();
        
        if ($result['lesson_count'] >= 20) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Maximum number of lessons reached for this student'
            ]);
            return;
        }
        
        // Check if the lesson date already exists for this student
        $checkDateQuery = "SELECT id FROM lessons WHERE student_id = :student_id AND lesson_date = :lesson_date";
        $checkDateStmt = $conn->prepare($checkDateQuery);
        $checkDateStmt->bindParam(':student_id', $input['studentId'], PDO::PARAM_INT);
        $checkDateStmt->bindParam(':lesson_date', $input['lessonDate']);
        $checkDateStmt->execute();
        
        if ($checkDateStmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'A lesson already exists for this date'
            ]);
            return;
        }
        
        // All checks passed, add the lesson
        $query = "
            INSERT INTO lessons 
                (student_id, lesson_date, notes) 
            VALUES 
                (:student_id, :lesson_date, :notes)
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':student_id', $input['studentId'], PDO::PARAM_INT);
        $stmt->bindParam(':lesson_date', $input['lessonDate']);
        $stmt->bindParam(':notes', $input['notes']);
        
        $stmt->execute();
        $newId = $conn->lastInsertId();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Lesson added successfully',
            'id' => $newId
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add lesson: ' . $e->getMessage()
        ]);
    }
}

// Delete a student
function deleteStudent($id) {
    global $conn;
    
    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Delete student (lessons will be deleted automatically due to ON DELETE CASCADE)
        $query = "DELETE FROM students WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            $conn->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Student not found'
            ]);
            return;
        }
        
        // Commit the transaction
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ]);
    } catch(PDOException $e) {
        $conn->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete student: ' . $e->getMessage()
        ]);
    }
}

// Get dashboard statistics
function getDashboardStats() {
    global $conn;
    
    try {
        // Total students
        $totalStudentsQuery = "SELECT COUNT(*) as total FROM students";
        $totalStudentsStmt = $conn->query($totalStudentsQuery);
        $totalStudents = $totalStudentsStmt->fetch()['total'];
        
        // Total lessons
        $totalLessonsQuery = "SELECT COUNT(*) as total FROM lessons";
        $totalLessonsStmt = $conn->query($totalLessonsQuery);
        $totalLessons = $totalLessonsStmt->fetch()['total'];
        
        // Graduated students (with 20 or more lessons)
        $graduatedQuery = "
            SELECT COUNT(DISTINCT student_id) as total 
            FROM lessons 
            GROUP BY student_id 
            HAVING COUNT(*) >= 20
        ";
        $graduatedStmt = $conn->query($graduatedQuery);
        $graduated = $graduatedStmt->rowCount();
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'totalStudents' => (int)$totalStudents,
                'totalLessons' => (int)$totalLessons,
                'graduatedStudents' => (int)$graduated
            ]
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch statistics: ' . $e->getMessage()
        ]);
    }
}

// Determine the request method and route
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$resource = $request[0] ?? null;
$id = $request[1] ?? null;

// Route the request to the appropriate handler
switch ($method) {
    case 'GET':
        if ($resource === 'students' && !$id) {
            getStudents();
        } elseif ($resource === 'students' && $id) {
            getStudent($id);
        } elseif ($resource === 'stats') {
            getDashboardStats();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid endpoint'
            ]);
        }
        break;
        
    case 'POST':
        if ($resource === 'students') {
            addStudent();
        } elseif ($resource === 'lessons') {
            addLesson();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid endpoint'
            ]);
        }
        break;
        
    case 'DELETE':
        if ($resource === 'students' && $id) {
            deleteStudent($id);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid endpoint'
            ]);
        }
        break;
        
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]);
        break;
}
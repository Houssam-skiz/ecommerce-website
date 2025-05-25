document.getElementById('studentForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  await fetch('add_student.php', { method: 'POST', body: formData });
  this.reset();
  loadStudents();
});

async function loadStudents() {
  const res = await fetch('get_students.php');
  const students = await res.json();

  const container = document.getElementById('studentsList');
  container.innerHTML = '';
  students.forEach(student => {
    const div = document.createElement('div');
    div.innerHTML = `
      <strong>${student.first_name} ${student.last_name}</strong> - ${student.age} سنة
      <button onclick="addSession(${student.id})">إضافة حصة</button>
      <button onclick="showSessions(${student.id})">عرض الحصص</button>
      <span> [${student.session_count}/20]</span>
    `;
    container.appendChild(div);
  });
}

async function addSession(id) {
  const date = prompt("أدخل تاريخ الحصة (YYYY-MM-DD):");
  if (date) {
    const formData = new FormData();
    formData.append('student_id', id);
    formData.append('session_date', date);
    await fetch('add_session.php', { method: 'POST', body: formData });
    loadStudents();
  }
}

async function showSessions(id) {
  const res = await fetch('get_sessions.php?student_id=' + id);
  const sessions = await res.json();
  alert("حصص المتدرب:\n" + sessions.map(s => s.session_date).join('\n'));
}

window.onload = loadStudents;

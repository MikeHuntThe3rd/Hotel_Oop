document.addEventListener('DOMContentLoaded', function () {
    const yearButtons = document.querySelectorAll('.year-button');
    yearButtons.forEach(button => button.addEventListener('click', onClickYearBtn));
});

/**
 * Handles the click event for year buttons.
 * @param {Event} event
 */
function onClickYearBtn(event) {
    // Remove active class from all year buttons
    const yearButtons = document.querySelectorAll('.year-button');
    yearButtons.forEach(button => button.classList.remove('active'));

    // Add active class to the clicked year button
    this.classList.add('active');

    const year = this.getAttribute('data-year');
    fetchClassesByYear(year);
}

/**
 * Fetches classes for the selected year via an AJAX request.
 * @param {string} year
 */
function fetchClassesByYear(year) {
    fetch('/school/year', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest', // Include this header for AJAX detection
        },
        body: JSON.stringify({ year: year })
    })
        .then(response => response.json())
        .then(data => updateClassesSection(data))
        .catch(error => console.error('Error fetching classes:', error));
}

/**
 * Updates the DOM to display the fetched classes.
 * @param {Object} data
 */
function updateClassesSection(data) {
    const classesSection = document.getElementById('classes-section');
    classesSection.innerHTML = ''; // Clear previous content

    if (data.classes && data.classes.length > 0) {
        data.classes.forEach(classItem => {
            const classButton = document.createElement('button');
            classButton.textContent = classItem.code;
            classButton.type = 'button';
            classButton.className = 'class-btn'; // Add class attribute
            classButton.dataset.classId = classItem.id; // Add data-class-id attribute

            // Add the click event to fetch students for this class
            classButton.addEventListener('click', function() {
                // Remove active class from all class buttons
                const classButtons = document.querySelectorAll('#classes-section button');
                classButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to the clicked class button
                classButton.classList.add('active');

                fetchStudentsByClassId(classItem.id);
            });

            classesSection.appendChild(classButton); // Append the button directly
        });
        // Add the students section after the class buttons
        // Dynamically add the students-section div after class buttons
        // const studentsSection = document.createElement('div');
        // studentsSection.id = 'students-section';
        // classesSection.insertAdjacentElement('afterend', studentsSection);
    } else {
        classesSection.textContent = 'No classes found for this year.';
    }
}

function fetchStudentsByClassId(classId) {
    fetch('/school/class', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ classId: classId }),
    })
        .then(response => response.json())
        .then(data => updateStudentsSection(data))
        .catch(error => console.error('Error fetching students:', error));
}

function updateStudentsSection(data) {
    const studentsSection = document.getElementById('students-section');
    if (data.students) {
        let studentsHtml = `
            <table>
                <thead>
                    <tr>
                        <th colspan="4">${data.class.code}</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Avg</th>
                    </tr>
                </thead>
                <tbody>
        `;
        data.students.forEach(student => {
            studentsHtml += `
                <tr>
                    <td>${student.id}</td>
                    <td><a href="#" class="student-name" data-student-id="${student.id}">${student.name}</a></td>
                    <td>${student.gender}</td>
                    <td class="align-right">${student.avg}</td>
                </tr>
                <tr class="subjects-row" id="student-${student.id}" style="display: none;">
                    <td>&nbsp;</td>
                    <td colspan="3">
                        <div class="student-content"></div>
                    </td>
                </tr>
            `;
        });
        studentsHtml += `
                </tbody>                
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td class="align-right">${data.class.avg}</td>
                    </tr>
                </tfoot>
            </table>`;
        studentsSection.innerHTML = studentsHtml;
        // Add event listeners to student names
        const studentNames = document.querySelectorAll('.student-name');
        studentNames.forEach(name => name.addEventListener('click', onClickStudentName));
    } else if (data.error) {
        studentsSection.innerHTML = `<p>${data.error}</p>`;
    }
}

/**
 * Handles the click event for student names.
 * @param {Event} event
 */
function onClickStudentName(event) {
    event.preventDefault();
    const studentId = this.getAttribute('data-student-id');
    const subjectsRow = document.getElementById(`student-${studentId}`);
    if (subjectsRow.style.display === 'none') {
        fetchStudentSubjects(studentId);
    } else {
        subjectsRow.style.display = 'none';
    }
}

/**
 * Fetches subjects and averages for the selected student via an AJAX request.
 * @param {string} studentId
 */
function fetchStudentSubjects(studentId) {
    fetch('/school/student', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ studentId: studentId })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Subjects fetched:', data);
            updateSubjectsSection(studentId, data);
        })
        .catch(error => console.error('Error fetching subjects:', error));
}

/**
 * Updates the DOM to display the fetched subjects and averages.
 * @param {string} studentId
 * @param {Object} data
 */
function updateSubjectsSection(studentId, data) {
    const subjectsRow = document.getElementById(`student-${studentId}`);
    let subjectsHtml = `
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Avg</th>
                </tr>
            </thead>
            <tbody>
    `;
    data.subjects.forEach(subject => {
        subjectsHtml += `
            <tr>
                <td><a href="#" class="subject-name" data-subject-id="${subject.id}" data-student-id="${studentId}">${subject.subject}</a></td>
                <td class="align-right">${subject.avg}</td>
            </tr>
            <tr class="marks-row" id="student-${studentId}-subject-${subject.id}" style="display: none;">
                <td>&nbsp;</td>
                <td colspan="3">
                    <div class="marks-content-${studentId}"></div>
                </td>
            </tr>
        `;
    });
    subjectsHtml += `
            </tbody>
        </table>
    `;
    subjectsRow.querySelector('.student-content').innerHTML = subjectsHtml;
    subjectsRow.style.display = 'table-row';
    // Add event listeners to student names
    const subjects = document.querySelectorAll('.subject-name');
    subjects.forEach(name => name.addEventListener('click', onClickSubjectName));
}

/**
 * Handles the click event for student names.
 * @param {Event} event
 */
function onClickSubjectName(event) {
    event.preventDefault();
    const studentId = this.getAttribute('data-student-id');
    const subjectId = this.getAttribute('data-subject-id');
    const subjectsRow = document.getElementById(`student-${studentId}-subject-${subjectId}`);
    if (subjectsRow.style.display === 'none') {
        fetchSubjectMarks(studentId, subjectId);
    } else {
        subjectsRow.style.display = 'none';
    }
}

/**
 * Fetches subjects and averages for the selected student via an AJAX request.
 * @param {string} studentId
 * @param {string} subjectId
 */
function fetchSubjectMarks(studentId, subjectId) {
    fetch('/school/subject', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ studentId: studentId, subjectId: subjectId })
    })
        .then(response => response.json())
        .then(data => {
            console.log('marks fetched:', data);
            updateMarksSection(studentId, subjectId, data);
        })
        .catch(error => console.error('Error fetching subjects:', error));
}

function updateMarksSection(studentId, subjectId, data) {
    const marksRow = document.getElementById(`student-${studentId}-subject-${subjectId}`);
    let marksHtml = `
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Mark</th>
                </tr>
            </thead>
            <tbody>
    `;
    // Check if data.marks is an array
    if (Array.isArray(data.marks)) {
        data.marks.forEach(mark => {
            marksHtml += `
                <tr>
                    <td>${mark.date}</td>
                    <td class="align-right">${mark.mark}</a></td>
                </tr>
            `;
        });
    } else {
        // Handle the case when data.marks is a single associative array
        marksHtml += `
            <tr>
                <td>${data.marks.date}</td>
                <td>${data.marks.mark}</td>
            </tr>
        `;
    }

    marksHtml += `
            </tbody>
        </table>
    `;
    marksRow.querySelector(`.marks-content-${studentId}`).innerHTML = marksHtml;
    marksRow.style.display = 'table-row';
}
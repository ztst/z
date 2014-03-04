function hideElement(element)
{
    element.style.display = "none";
}

function showElement(element)
{
    element.style.display = "block";
}

var tabGrades = document.getElementById("tab_grades");
var tabSubjects = document.getElementById("tab_subjects");
var listGrades = document.getElementById("grade_1_subjects");
var listSubjects = document.getElementById("grade_1_subjects");

document.writeln("test");

tabGrades.onmouseover = showElement(listGrades);
tabGrades.onmouseout = hideElement(listGrades);
tabSubjects.onmouseover = showElement(listSubjects);
tabSubjects.onmouseout = hideElement(listSubjects);
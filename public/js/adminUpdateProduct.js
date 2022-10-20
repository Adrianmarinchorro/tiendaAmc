window.onload = function() {
    if (this.value==1) {
        document.getElementById("updateForm").action = "/adminProduct/updateCourse/";
        document.getElementById("book").style.display = "none";
        document.getElementById("course").style.display = "block";
    } else if(this.value==2) {
        document.getElementById("updateForm").action = "/adminProduct/updateBook/";
        document.getElementById("book").style.display = "block";
        document.getElementById("course").style.display = "none";
    }
}
window.onload = function() {
    document.getElementById("book").style.display = "none";
    document.getElementById("course").style.display = "none";
    document.getElementById("createForm").action = "/adminProduct/createCourse/";

    //detectamos el cambio en el select
    document.getElementById("type").onchange = function() {
        if (this.value==1) {
            document.getElementById("createForm").action = "/adminProduct/createCourse/";
            document.getElementById("book").style.display = "none";
            document.getElementById("course").style.display = "block";
        } else if(this.value==2) {
            document.getElementById("createForm").action = "/adminProduct/createBook/";
            document.getElementById("book").style.display = "block";
            document.getElementById("course").style.display = "none";
        }
    }
}
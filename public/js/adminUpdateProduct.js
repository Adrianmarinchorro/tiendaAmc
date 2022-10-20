window.onload = function() {
    document.getElementById("type").onload = function() {
        if (this.value==1) {
            document.getElementById("createForm").action = "/adminProduct/UpdateCourse/<?= $data['product']->id ?>";
            document.getElementById("book").style.display = "none";
            document.getElementById("course").style.display = "block";
        } else if(this.value==2) {
            document.getElementById("createForm").action = "/adminProduct/updateBook/<?= $data['product']->id ?>";
            document.getElementById("book").style.display = "block";
            document.getElementById("course").style.display = "none";
        }
    }
}
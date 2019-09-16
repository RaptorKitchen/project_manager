function createNewProject() {
    let new_project = $("#new_project").val();
    if (new_project) {
        $("#createProject").attr("action","/projects/create/"+new_project);
    }
}

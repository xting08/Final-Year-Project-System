function updateItemTodo (itemTodoId, isComplete){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update-todo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let is_complete = isComplete ? 1 : 0;
    xhr.send("item_todo_id=" + itemTodoId + "&is_complete=" + is_complete);
}
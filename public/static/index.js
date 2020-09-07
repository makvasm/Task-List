let createForm = document.getElementById("createTaskForm");

createForm.addEventListener("submit", event => {
  event.preventDefault();
  let name = event.target.name.value;
  let email = event.target.email.value;
  let text = event.target.text.value;

  CreateTask(name, email, text);
});


document.querySelectorAll("tr").forEach(tr => {
  tr.addEventListener("change", event => {
    if(!event.currentTarget.changed){
      event.currentTarget.append(SaveButton(event.currentTarget));
      event.currentTarget.changed = true;
    }
  });
});

function CreateTask(name, email, text){
  let body = {
    name,
    email,
    text,
    action: "CREATE"
  };

  fetch("/tasks", {
    method: "POST",
    headers: {
      "Content-Type": "applicatiob/json"
    },
    body: JSON.stringify(body)
  })
  .then(res => {
    window.location.reload();
  })
}

function UpdateTask(element) {
  let body = {
    id,
    text,
    complete,
  } = GetTaskFields(element);
  body.action = "UPDATE";

  fetch("/tasks", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(body)
  })
    .then(res => {
    })
}

function GetTaskFields(tr) {
  let complete = tr.querySelector("input[type=checkbox").checked;
  let text = tr.querySelector("textarea").value;
  let id = tr.dataset.taskId;
  return {id, text, complete};
}

function SaveButton(parent) {
  let btn = document.createElement("button");
  btn.style.cssText = `
    position: absolute;
    maegin: auto;
  `;
  btn.className = "btn btn-success update-task";
  btn.textContent = "Save";
  btn.type = "button";
  btn.addEventListener("click", event => {
    UpdateTask(parent);
    event.currentTarget.remove();
    parent.changed = false;
  })
  return btn;
}
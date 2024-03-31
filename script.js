const taskNameEl = document.querySelector("#task-name");
const descriptionEl = document.querySelector("#description");
const addBtn = document.querySelector("#add-button");
const todoListEl = document.querySelector("#todo-list");
const logoutButton = document.querySelector("#logout-button");

const URL = "http://localhost/basicCrud";

addBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  const name = taskNameEl.value;
  const description = descriptionEl.value;

  const res = await addTodo({ name, description });

  taskNameEl.value = "";
  descriptionEl.value = "";

  refresh();
});

refresh();

async function refresh() {
  const { userInfo, todos } = await getUserInfo();
  console.log(userInfo, todos);
  renderTodoList(todos, todoListEl);
  renderInfo(userInfo);
}

async function addTodo(todo) {
  const res = await fetch(URL + "/api.php", {
    method: "POST",
    body: JSON.stringify(todo),
    headers: {
      "Content-Type": "application/json",
    },
  });

  const data = await res.json();
  return data;
}

async function getUserInfo() {
  const res = await fetch(URL + "/api.php", {
    method: "GET",
  });

  const data = await res.json();
  return data;
}

function renderInfo(data) {
  const usernameField = document.querySelector("#username");
  usernameField.innerText = data.username;

  console.log(data);
}

function renderTodoList(list, parentEl) {
  parentEl.innerHTML = "";
  list.forEach(({ id, name, description }) => {
    const todoEl = document.createElement("div");
    const rmTodoEl = document.createElement("button");

    todoEl.innerHTML = `
    <h3>${name}</h3>
    <p>${description}</p>
    `;

    rmTodoEl.innerText = "Remove";
    rmTodoEl.addEventListener("click", (e) => {
      removeTodo(id);
      refresh();
    });

    todoEl.appendChild(rmTodoEl);

    parentEl.appendChild(todoEl);
  });
}

async function removeTodo(id) {
  const res = await fetch(URL + "/api.php", {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id }),
  });

  const data = await res.json();
  return data;
}

//Modal logic
const modalEl = document.querySelector("#modal");
const modalBtn = document.querySelector("#modal-button");
const modalCancelBtn = document.querySelector("#cancel-button");

modalBtn.addEventListener("click", (e) => {
  e.preventDefault();
  modalEl.classList.remove("hidden");
});
modalCancelBtn.addEventListener("click", (e) => {
  e.preventDefault;
  modalEl.classList.add("hidden");
});

addBtn.addEventListener("click", (e) => {
  e.preventDefault();
  modalEl.classList.add("hidden");
});

logoutButton.addEventListener("click", logout);

async function logout() {
  await fetch("http://localhost/basicCrud/logout.php");
  window.location.href = "http://localhost/basicCrud/index.php";
}

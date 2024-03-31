const burguerMenus = document.querySelectorAll(".burguer-menu");

burguerMenus.forEach((burguerMenu) => {
  const burguerBtn = burguerMenu.querySelector(".burguer-button");
  const burguerContent = burguerMenu.querySelector(".burguer-content");

  let mouseon = false;
  let displaying = false;
  burguerContent.style.display = "none";

  burguerBtn.addEventListener("click", () => {
    displaying = !displaying;
    burguerContent.style.display = displaying ? "block" : "none";
  });
});

// Kiểm tra sự tồn tại của nút trước khi thêm sự kiện
const adressbtn = document.querySelector("#adress-form");
const adressclose = document.querySelector("#adress-close");

if (adressbtn && adressclose) {
  adressbtn.addEventListener("click", function () {
    const adressForm = document.querySelector(".adress-form");
    if (adressForm) adressForm.style.display = "flex";
  });

  adressclose.addEventListener("click", function () {
    const adressForm = document.querySelector(".adress-form");
    if (adressForm) adressForm.style.display = "none";
  });
}

// Slider
const rightbtn = document.querySelector(".fa-arrow-right");
const leftbtn = document.querySelector(".fa-arrow-left");
const imgNuber = document.querySelectorAll(".slider-content-left-top img");
let index = 0;

if (rightbtn && leftbtn && imgNuber.length > 0) {
  rightbtn.addEventListener("click", function () {
    index = index + 1;
    if (index > imgNuber.length - 1) {
      index = 0;
    }
    document.querySelector(".slider-content-left-top").style.right =
      index * 100 + "%";
  });

  leftbtn.addEventListener("click", function () {
    index = index - 1;
    if (index < 0) {
      index = imgNuber.length - 1;
    }
    document.querySelector(".slider-content-left-top").style.right =
      index * 100 + "%";
  });
}

// Slider 1
const imgNuberLi = document.querySelectorAll(".slider-content-left-bottom li");

if (imgNuberLi.length > 0) {
  imgNuberLi.forEach(function (image, idx) {
    image.addEventListener("click", function () {
      removeactive();
      document.querySelector(".slider-content-left-top").style.right =
        idx * 100 + "%";
      image.classList.add("active");
    });
  });

  function removeactive() {
    const imgactive = document.querySelector(".slider-content-left-bottom .active");
    if (imgactive) imgactive.classList.remove("active");
  }
}

// Slider 2 (Tự động chuyển ảnh)
function imgAuto() {
  index = index + 1;
  if (index > imgNuber.length - 1) {
    index = 0;
  }
  removeactive();
  document.querySelector(".slider-content-left-top").style.right =
    index * 100 + "%";
  if (imgNuberLi[index]) imgNuberLi[index].classList.add("active");
}

if (imgNuber.length > 0) {
  setInterval(imgAuto, 5000);
}

// Dropdown menu toggle
const dropdownToggle = document.querySelector(".dropdown-toggle");
if (dropdownToggle) {
  dropdownToggle.addEventListener("click", function (event) {
    event.preventDefault();
    const menu = document.querySelector(".dropdown-menu");
    if (menu) menu.classList.toggle("show");
  });
}


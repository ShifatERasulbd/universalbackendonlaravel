document.addEventListener("DOMContentLoaded", () => {
const navToggle = document.querySelector(".nav-toggle");
const navMenu = document.querySelector(".menu");
const navBackdrop = document.querySelector(".nav-backdrop");

if(navToggle && navMenu && navBackdrop){
const closeMenu = () => {
document.body.classList.remove("menu-open");
navToggle.setAttribute("aria-expanded", "false");
};

const openMenu = () => {
document.body.classList.add("menu-open");
navToggle.setAttribute("aria-expanded", "true");
};

navToggle.addEventListener("click", () => {
if(document.body.classList.contains("menu-open")){
closeMenu();
}else{
openMenu();
}
});

navBackdrop.addEventListener("click", closeMenu);

navMenu.addEventListener("click", (event) => {
if(event.target.closest("li")){
closeMenu();
}
});

document.addEventListener("keydown", (event) => {
if(event.key === "Escape"){
closeMenu();
}
});

window.addEventListener("resize", () => {
if(window.innerWidth > 900){
closeMenu();
}
});
}

const lightbox = document.getElementById("imageLightbox");
const lightboxImage = document.getElementById("lightboxImage");
const lightboxClose = document.getElementById("lightboxClose");
const galleryImages = document.querySelectorAll(".gallery-page .service-card img");

if(!lightbox || !lightboxImage || !lightboxClose || !galleryImages.length){
return;
}

const closeLightbox = () => {
lightbox.classList.remove("open");
lightbox.setAttribute("aria-hidden", "true");
lightboxImage.src = "";
lightboxImage.alt = "";
};

galleryImages.forEach((image) => {
image.addEventListener("click", () => {
lightboxImage.src = image.src;
lightboxImage.alt = image.alt;
lightbox.classList.add("open");
lightbox.setAttribute("aria-hidden", "false");
});
});

lightboxClose.addEventListener("click", closeLightbox);

lightbox.addEventListener("click", (event) => {
if(event.target === lightbox){
closeLightbox();
}
});

document.addEventListener("keydown", (event) => {
if(event.key === "Escape" && lightbox.classList.contains("open")){
closeLightbox();
}
});
});

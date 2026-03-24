const counters = document.querySelectorAll(".count");
const counterSection = document.querySelector(".counter");

const animateCounter = (counter) => {
const target = Number(counter.dataset.count);
const duration = 1800;
const startTime = performance.now();

const step = (currentTime) => {
const progress = Math.min((currentTime - startTime) / duration, 1);
counter.innerText = Math.floor(progress * target);

if(progress < 1){
requestAnimationFrame(step);
}else{
counter.innerText = target;
}
};

requestAnimationFrame(step);
};

if(counterSection && counters.length){
const counterObserver = new IntersectionObserver((entries, observer) => {
entries.forEach((entry) => {
if(entry.isIntersecting){
counters.forEach((counter) => animateCounter(counter));
observer.unobserve(entry.target);
}
});
}, { threshold: 0.35 });

counterObserver.observe(counterSection);
}

const heroSlides = document.querySelectorAll(".hero-slide");

if(heroSlides.length > 1){

let currentSlide = 0;

setInterval(() => {

heroSlides[currentSlide].classList.remove("active");

currentSlide = (currentSlide + 1) % heroSlides.length;

heroSlides[currentSlide].classList.add("active");

}, 5000);

}

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

const cvFileInput = document.getElementById("cvFileInput");
const photoFileInput = document.getElementById("photoFileInput");
const cvFileName = document.getElementById("cvFileName");
const photoFileName = document.getElementById("photoFileName");
const photoPreview = document.getElementById("photoPreview");

if(cvFileInput && cvFileName){
cvFileInput.addEventListener("change", () => {
const file = cvFileInput.files && cvFileInput.files[0] ? cvFileInput.files[0] : null;
cvFileName.textContent = file ? `Selected CV: ${file.name}` : "";
});
}

if(photoFileInput && photoFileName && photoPreview){
photoFileInput.addEventListener("change", () => {
const file = photoFileInput.files && photoFileInput.files[0] ? photoFileInput.files[0] : null;

if(!file){
photoFileName.textContent = "";
photoPreview.style.display = "none";
photoPreview.src = "";
return;
}

photoFileName.textContent = `Selected Photo: ${file.name}`;

const reader = new FileReader();
reader.onload = (event) => {
photoPreview.src = event.target?.result || "";
photoPreview.style.display = "block";
};
reader.readAsDataURL(file);
});
}
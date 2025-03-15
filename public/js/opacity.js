


document.addEventListener("DOMContentLoaded", function() {



    let th = document.querySelector(`[data-nivel-id="${nivelId}"]`);
    let buttonContainer = th.querySelector(".position-absolute");

    th.addEventListener("mouseenter", function() {
        buttonContainer.style.opacity = "1";
    });

    th.addEventListener("mouseleave", function() {
        buttonContainer.style.opacity = "0";
    });
});

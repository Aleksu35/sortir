import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.querySelectorAll('.navbar a').forEach(link => {
    link.addEventListener('mouseover', () => {
        // Crée les étoiles lorsque la souris entre dans le lien
        createDiagonalStars(link);
    });

    link.addEventListener('mouseout', () => {
        // Supprime les étoiles lorsque la souris quitte le lien
        removeStars(link);
    });
});

function createDiagonalStars(link) {
    // Gérer la position en diagonale des étoiles
    const starPositions = [
        { top: '-5px', left: '-5px' },
        { top: '100%', left: '100%' },
        { top: '100%', left: '10%' }
    ];

    starPositions.forEach(position => {
        const star = document.createElement('span');
        star.classList.add('star');
        star.style.top = position.top;
        star.style.left = position.left;
        star.style.opacity = 1;

        link.appendChild(star);
    });
}

function removeStars(link) {
    // Trouve toutes les étoiles et les supprime
    const stars = link.querySelectorAll('.star');
    stars.forEach(star => star.remove());
}

/*
document.addEventListener("DOMContentLoaded", function() {

    const editButtons = document.querySelectorAll(".edit-btn");
    const formRows = document.querySelectorAll(".edit-form-row");

    editButtons.forEach((button, index) => {
        button.addEventListener("click", function() {

            formRows.forEach(row => row.style.display = "none");

            formRows[index].style.display = "block";
        });
    });


    const cancelButtons = document.querySelectorAll(".cancel-btn");
    cancelButtons.forEach((button, index) => {
        button.addEventListener("click", function() {
            formRows[index].style.display = "none";
        });
    });
});*/
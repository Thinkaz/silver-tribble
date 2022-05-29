const confetti = require('canvas-confetti');

let myCanvas = document.querySelector('.animations');
confetti.create(myCanvas, {
    resize: true,
    useWorker: true
});

console.log("confetti()", confetti);

let duration = 15 * 1000;
let animationEnd = Date.now() + duration;
let skew = 1;

function randomInRange(min, max) {
    return Math.random() * (max - min) + min;
}
(function frame() {
    let timeLeft = animationEnd - Date.now();
    let ticks = Math.max(200, 500 * (timeLeft / duration));
    skew = Math.max(0.8, skew - 0.001);

    confetti({
        particleCount: 1,
        startVelocity: 0,
        ticks: ticks,
        origin: {
            x: Math.random(),
            y: (Math.random() * skew) - 0.2
        },
        colors: ['#ffffff'],
        shapes: ['circle'],
        gravity: randomInRange(0.4, 0.6),
        scalar: randomInRange(0.4, 1),
        drift: randomInRange(-0.4, 0.4)
    });

    if (timeLeft > 0) {
        requestAnimationFrame(frame);
    }
}());
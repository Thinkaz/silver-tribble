const { Fireworks } = require('fireworks-js');


const options = {
    rocketsPoint: 50,
    opacity: .5,
    speed: 2,
    acceleration: 1.05,
    friction: .95,
    particles: 50,
    trace: 3,
    explosion: 5,
    autoresize: true,
    hue: { min: 0, max: 360 },
    delay: { min: 15, max: 30 },
    boundaries: { visible: false, x: 50, y: 50, width: container.clientWidth, height: container.clientHeight },
    sound: { enabled: false },
    mouse: { click: false, move: false, max: 3 },
    brightness: { min: 50, max: 80, decay: { min: 0.015, max: 0.03 } }
}


const container = document.querySelector('.animations');
const fireworks = new Fireworks(container, options);
fireworks.start();
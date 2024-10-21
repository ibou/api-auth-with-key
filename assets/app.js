import './bootstrap.js';

import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

import confetti from 'canvas-confetti'

document.body.addEventListener('dblclick', () => {
    confetti()
})

// Medidor de fuerza de contraseña para el registro. Puramente informativo:
// no bloquea el envío del formulario, solo orienta al usuario mientras
// escribe. La validación real de la contraseña vive en el backend.

const COMMON = /^(?:password|passw0rd|qwerty|letmein|welcome|admin|iloveyou|monkey|dragon|abc123|111111|123123|123456)/i;
const RUN = /(.)\1{3,}/;
const RUN_UP = /(?:0123|1234|2345|3456|4567|5678|6789|abcd|bcde|cdef|defg|qwer|wert|erty|asdf)/i;
const SYMBOL = /[!-/:-@[-`{-~]/;

const RULES = [
    { id: 'length', test: (v) => v.length >= 12 },
    { id: 'case', test: (v) => /[a-z]/.test(v) && /[A-Z]/.test(v) },
    { id: 'digit', test: (v) => /\d/.test(v) },
    { id: 'symbol', test: (v) => SYMBOL.test(v) },
];

const LABELS = ['Vacío', 'Débil', 'Aceptable', 'Buena', 'Fuerte'];

function evaluate(value) {
    const evaluated = RULES.map((rule) => ({ ...rule, met: rule.test(value) }));
    const passed = evaluated.filter((rule) => rule.met).length;
    const guessable = value.length > 0 && (COMMON.test(value) || RUN.test(value) || RUN_UP.test(value));

    const score = value.length === 0
        ? 0
        : guessable
            ? 1
            : Math.min(RULES.length, Math.max(1, passed));

    return { score, evaluated, guessable, label: LABELS[Math.min(score, LABELS.length - 1)] };
}

function toneFor(score) {
    if (score === 0) return 'none';
    const ratio = score / RULES.length;
    if (ratio <= 0.34) return 'danger';
    if (ratio <= 0.67) return 'caution';
    return 'safe';
}

export function initPasswordStrength() {
    document.querySelectorAll('[data-password-strength]').forEach((wrap) => {
        const input = document.querySelector(wrap.dataset.target);

        if (!input) {
            return;
        }

        const bars = wrap.querySelectorAll('.password-strength-bar');
        const labelEl = wrap.querySelector('.password-strength-label');
        const warningEl = wrap.querySelector('.password-strength-warning');
        const announcementEl = wrap.querySelector('[data-password-strength-announcement]');

        let announceTimer = null;

        const render = () => {
            const { score, evaluated, guessable, label } = evaluate(input.value);

            wrap.dataset.tone = toneFor(score);

            bars.forEach((bar, i) => bar.classList.toggle('is-filled', i < score));

            labelEl.textContent = input.value.length === 0 ? '' : label;
            warningEl.classList.toggle('is-visible', guessable);

            evaluated.forEach((rule) => {
                wrap.querySelector(`[data-rule="${rule.id}"]`)?.classList.toggle('is-met', rule.met);
            });

            clearTimeout(announceTimer);

            if (input.value.length === 0) {
                announcementEl.textContent = '';
                return;
            }

            announceTimer = setTimeout(() => {
                const unmet = evaluated
                    .filter((rule) => !rule.met)
                    .map((rule) => wrap.querySelector(`[data-rule="${rule.id}"]`)?.dataset.label)
                    .filter(Boolean);

                announcementEl.textContent = [
                    `Fuerza de la contraseña: ${label.toLowerCase()}.`,
                    guessable ? 'Este es un patrón común y fácil de adivinar.' : '',
                    unmet.length === 0 ? 'Cumple con todos los requisitos.' : `Aún falta: ${unmet.join(', ')}.`,
                ].filter(Boolean).join(' ');
            }, 700);
        };

        input.addEventListener('input', render);
        render();
    });
}

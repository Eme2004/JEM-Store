{{-- Modal de confirmación genérico para acciones destructivas. Sustituye a
     confirm() nativo del navegador: se activa vía data-confirm-submit en
     cualquier <form>, ver resources/js/app.js. --}}
<div class="jem-modal d-none" data-confirm-modal role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle">
    <div class="jem-modal__backdrop" data-confirm-modal-cancel></div>

    <div class="jem-modal__panel">
        <h2 class="jem-modal__title" id="confirmModalTitle">
            Confirmar acción
        </h2>

        <p class="jem-modal__message" data-confirm-modal-message></p>

        <div class="jem-modal__actions">
            <button type="button" class="btn jem-modal__cancel" data-confirm-modal-cancel>
                Cancelar
            </button>

            <button type="button" class="btn jem-modal__confirm" data-confirm-modal-confirm>
                Eliminar
            </button>
        </div>
    </div>
</div>

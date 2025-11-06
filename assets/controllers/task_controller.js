import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';

export default class extends Controller {
    async openModalCreate({params: {url}}) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const result = await response.text();

            document.body.insertAdjacentHTML('beforeend', result);

            const modalElement = document.getElementById('modal-create-task');
            const modal = new Modal(modalElement);
            modal.show();

            modalElement.addEventListener('hidden.bs.modal', () => {
                modal.dispose();
                modalElement.remove();
            }, {once: true});
        } catch (error) {
            console.error(error.message);
        }
    }

    async submitFormModalCreate(event) {
        event.preventDefault();
        try {
            const form = event.target;
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
            });

            if (response.ok) {
                const {redirect} = await response.json();
                setTimeout(() => window.location.href = redirect, 150);
            } else {
                const html = await response.text();
                const modalElement = document.getElementById('modal-create-task');
                modalElement.innerHTML = html;
            }
        } catch (error) {
            console.error(error.message);
        }
    }
}

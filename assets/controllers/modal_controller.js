import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';

export default class extends Controller {
    static values = {
        url: String
    };

    modal = null;

    async open(event) {
        event?.preventDefault();

        try {
            const url = event?.params?.url || this.urlValue;

            if (!url) {
                throw new Error('No URL provided for modal');
            }

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const html = await response.text();
            document.body.insertAdjacentHTML('beforeend', html);

            const modalElement = document.body.querySelector('.modal:last-of-type');

            if (!modalElement) {
                throw new Error('Modal element not found in response');
            }

            this.modal = new Modal(modalElement);
            this.modal.show();

            modalElement.addEventListener('hidden.bs.modal', () => {
                this.cleanup(modalElement);
            }, {once: true});
        } catch (error) {
            console.error('Error opening modal:', error.message);
        }
    }

    async submit(event) {
        event.preventDefault();

        try {
            const form = event.target;
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
            });

            if (response.ok) {
                const data = await response.json();

                if (data.redirect) {
                    setTimeout(() => window.location.href = data.redirect, 150);
                } else {
                    this.close();
                }
            } else {
                await this.replaceModalContent(response);
            }
        } catch (error) {
            console.error('Error submitting form:', error.message);
        }
    }

    async replaceModalContent(response) {
        const html = await response.text();
        const modalElement = this.element.closest('.modal') || document.body.querySelector('.modal:last-of-type');

        if (!modalElement) return;

        const temp = document.createElement('div');
        temp.innerHTML = html;
        const newModalDialog = temp.querySelector('.modal-dialog');

        if (newModalDialog) {
            modalElement.querySelector('.modal-dialog').replaceWith(newModalDialog);
        }
    }

    close() {
        if (this.modal) {
            this.modal.hide();
        }
    }

    cleanup(modalElement) {
        if (this.modal) {
            this.modal.dispose();
            this.modal = null;
        }
        modalElement.remove();
    }
}

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

            const modal = new Modal(document.getElementById('modal-create-task'));
            modal.show();
        } catch (error) {
            console.error(error.message);
        }
    }
}

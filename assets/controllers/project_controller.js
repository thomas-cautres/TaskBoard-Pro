import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';

export default class extends Controller {
    static targets = ["projectCard"];
    currentProjectCard;

    selectType(event) {
        this.projectCardTargets.forEach(c => c.classList.remove('selected'));
        this.currentProjectCard = event.currentTarget;
        this.currentProjectCard.classList.add('selected');
        this.currentProjectCard.querySelector('input[type="radio"]').checked = true;
    }

    async openModalArchive({params: {url}}) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const result = await response.text();

            document.body.insertAdjacentHTML('beforeend', result);

            const modal = new Modal(document.getElementById('modal-archive-project'));
            modal.show();
        } catch (error) {
            console.error(error.message);
        }
    }

    async openModalRestore({params: {url}}) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const result = await response.text();

            document.body.insertAdjacentHTML('beforeend', result);

            const modal = new Modal(document.getElementById('modal-restore-project'));
            modal.show();
        } catch (error) {
            console.error(error.message);
        }
    }
}

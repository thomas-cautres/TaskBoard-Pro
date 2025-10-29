import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["projectCard"];
    currentProjectCard;

    selectType(event) {
        this.projectCardTargets.forEach(c => c.classList.remove('selected'));
        this.currentProjectCard = event.currentTarget;
        this.currentProjectCard.classList.add('selected');
        this.currentProjectCard.querySelector('input[type="radio"]').checked = true;
    }
}

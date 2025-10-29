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

    filter(event) {
        const searchParams = new URLSearchParams(window.location.search);

        if (event.target.value) {
            searchParams.set('filters[name]', event.target.value);
        } else {
            searchParams.delete('filters[name]');
        }

        window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
    }
}

import {Controller} from '@hotwired/stimulus';
import {getComponent} from '@symfony/ux-live-component';
import Swal from 'sweetalert2'
import "tom-select/dist/css/tom-select.bootstrap5.css";
import TomSelect from "tom-select/base";


export default class extends Controller {
    selectedImages = [];
    pv = 3;

    async initialize() {
        this.component = await getComponent(this.element);



        new TomSelect(this.element.querySelector('.players'),{
            persist: false,
            createOnBlur: true,
            create: true

        });

        this.pv = this.component.valueStore.get('pv');

        this.element.querySelector('.restart').addEventListener('click',()=>{
            window.location.reload();
        })
        this.element.querySelector('.display-all').addEventListener('click',(e)=>{
            e.target.classList.toggle('active');
            if(e.target.classList.contains('active')){
                this.showAll();
                return;
            }
            this.hideAll();
        })

        this.cardsElements = this.element.querySelectorAll('.game-card-wrapper');

        this.cardsElements.forEach(element => {
            element.addEventListener('click', () => {
                console.log(this.selectedImages);
                if (this.selectedImages.length >= 2) {
                    this.hideAll();
                }
                element.classList.toggle('visible');

                if (element.classList.contains('visible')) {
                    this.selectedImages.push(element);
                } else {
                    this.selectedImages = this.selectedImages.filter(e => e !== element);
                }

                if (this.selectedImages.length === 2) {
                    this.move();
                }
            })
        })
    }

    hideAll() {
        let delay = 0;
        this.cardsElements.forEach(element => {
            element.classList.remove('visible');
            // setTimeout(() => {
            //     element.classList.remove('visible');
            // }, delay);
            // delay += 50;
        })
        this.selectedImages = [];
        console.log(this.selectedImages);
    }


    showAll() {
        let delay = 0;
        this.cardsElements.forEach(element => {
            setTimeout(() => {
                element.classList.add('visible');
            }, delay);
            delay += 50;
        })
        this.selectedImages = [];
        console.log(this.selectedImages);
    }


    move() {
        const [first, second] = this.selectedImages;

        this.component.action('move');

        let firstIsMain = first.dataset.isMain === 'true';
        let secondIsMain = second.dataset.isMain === 'true';

        if(firstIsMain && secondIsMain){
            this.win();
            return;
        }


        if (first.id !== second.id) {
            this.component.action('updatePv', {value: -1}).then(() => {
                this.pv = this.component.valueStore.get('pv');
                console.log(this.pv)
                if (this.pv === 0) {
                    this.lose();
                }
            })
        }
    }

    lose() {
        Swal.fire({
            title: 'Perdu !',
            icon: 'error',
            confirmButtonText: 'Nouvelle partie',
        })
    }

    win() {
        Swal.fire({
            title: "Gagné !",
            icon: "success",
            confirmButtonText: 'Nouvelle partie',
        });
    }

}

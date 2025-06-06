import {Controller} from '@hotwired/stimulus';
import {getComponent} from '@symfony/ux-live-component';
import Swal from 'sweetalert2'
import "tom-select/dist/css/tom-select.bootstrap5.css";
import TomSelect from "tom-select/base";
import "./../../styles/game/cards.scss";


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

        this.startPartyform = document.querySelector('form[name="start-party-form"]');
        this.startPartyform.addEventListener('submit',(e)=>{
            e.preventDefault();
            let playerName = this.startPartyform.querySelector('[name="playerName"]').value;
            this.component.action('startParty', {playerName: playerName});
        })

        this.component.on('render:finished', (component) => {
            this.bindCard();
        });

        // window.addEventListener('game:card:start', () => {
        //     this.bindCard();
        // });

        // this.pv = this.component.valueStore.get('pv');
        //
        // this.element.querySelector('.restart').addEventListener('click',()=>{
        //     window.location.reload();
        // })
        // this.element.querySelector('.display-all').addEventListener('click',(e)=>{
        //     e.target.classList.toggle('active');
        //     if(e.target.classList.contains('active')){
        //         this.showAll();
        //         return;
        //     }
        //     this.hideAll();
        // })
        //
        // this.cardsElements = this.element.querySelectorAll('.game-card-wrapper');
    }

    bindCard(){
        this.cards = this.element.querySelectorAll('.cards .card-wrapper');

        this.cards.forEach(card => {
            card.addEventListener('click', () => {
                console.log(this.selectedImages);
                if (this.selectedImages.length >= 2) {
                    this.hideAll();
                }
                card.classList.toggle('visible');

                if (card.classList.contains('visible')) {
                    this.selectedImages.push(card);
                } else {
                    this.selectedImages = this.selectedImages.filter(e => e !== card);
                }

                if (this.selectedImages.length === 2) {
                    this.move();
                }
            })
        })
    }

    hideAll() {
        let delay = 0;
        this.cards.forEach(element => {
            element.classList.remove('visible');
            // setTimeout(() => {
            //     element.classList.remove('visible');
            // }, delay);
            // delay += 50;
        })
        this.selectedImages = [];
    }

    showAll() {
        let delay = 0;
        this.cards.forEach(element => {
            setTimeout(() => {
                element.classList.add('visible');
            }, delay);
            delay += 50;
        })
        this.selectedImages = [];
    }


    move() {
        const [first, second] = this.selectedImages;

        this.component.action('move', {
            'cardAId' : first.id,
            'cardBId' : second.id,
        });

        let firstIsMain = first.dataset.isMain === 'true';
        let secondIsMain = second.dataset.isMain === 'true';

        if(firstIsMain && secondIsMain){
            this.win();
        }


        // if (first.id !== second.id) {
        //     this.component.action('updatePv', {value: -1}).then(() => {
        //         this.pv = this.component.valueStore.get('pv');
        //         console.log(this.pv)
        //         if (this.pv === 0) {
        //             this.lose();
        //         }
        //     })
        // }
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

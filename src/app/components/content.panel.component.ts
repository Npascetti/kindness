import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Status} from "../classes/status";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import 'rxjs/add/observable/of';


@Component({
	templateUrl: "./templates/content-panel.html",
	selector: "content-panel"
})

export class ContentPanelComponent implements OnInit{
    // empty array of lat/long points
    public positions: any = [];

    constructor() {}

    ngOnInit() : void {

        // onInit grab array of map points
        this.positions = this.getRandomMarkers();
    }

    // this is a dummy example. In your app this is where you should bring
    // in all the location points through your service
    getRandomMarkers() : any {
        let randomLat: number, randomLng: number;

        let positions = [];
        for (let i = 0 ; i < 9; i++) {
            randomLat = Math.random() * (43.7399 - 43.7300) + 43.7300;
            randomLng = Math.random() * (-79.7600 - -79.7699) + -79.7699;
            positions.push([randomLat, randomLng]);
        }
        return positions;
    }

    // this is an example that uses an Observable - much like
    // the call to your service. This works, and is called when
    // the button is pushed...
    showMarkersFromObservable() {
        Observable.of(this.getRandomMarkers()) // Think this as http call
            .subscribe( positions => {
                this.positions = positions;
            });
    }

}


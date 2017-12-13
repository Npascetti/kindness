import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Status} from "../classes/status";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import 'rxjs/add/observable/of';
import {HubService} from "../services/hub.service";
import {Hub} from "../classes/hub";
import {forEach} from "@angular/router/src/utils/collection";


@Component({
    templateUrl: "./templates/content-panel.html",
    selector: "content-panel"
})

export class ContentPanelComponent implements OnInit {
    // empty array of lat/long points
    // public positions: any = [];
    hub: Hub = new Hub(null, null, null, null);
    hubs: any = [];
    data: Observable<Array<Hub[]>>;
    hubData: Hub[] = [];

    constructor(private hubService: HubService) {
    }

    ngOnInit(): void {
        // onInit grab array of map points
        // this.getAllHubs();
        //  this.data = new Observable( hubs => {
        //     this.hubService.getAllHubs().subscribe(hubData => {
        //         this.hubData = hubData;
        //     });
        //  });
        this.showMarkersFromObservable();
        //  // for (let i = 0 ; i < this.hubs.length; i++) {
        //  //     console.log(this.hubs[i].hubLocation);
        //  // }
        //  console.log(this.data);
    }

    // this is a dummy example. In your app this is where you should bring
    // in all the location points through your service
    getAllHubs(): any {
        // let hubAddress: string;
        this.hubService.getHubPoints()
            .subscribe(hubData => {
                this.hubData = hubData;
                for (let i = 0; i < this.hubs.length; i++) {
                    console.log(this.hubs[i].hubLocation);
                }
                console.log(hubData);
                return hubData;
            });

    }

    // this is an example that uses an Observable - much like
    // the call to your service. This works, and is called when
    // the button is pushed...
    showMarkersFromObservable() {
        console.log(this.hubData);
        Observable.of(this.getAllHubs()) // Think this as http call
            .subscribe(hubData => {
                this.hubData = hubData;
                // console.log(this.hubData);
                // console.log('fuck me');
                // console.log(this.hubData);
            });

    }

    // getRandomMarkers() : any {
    //     let randomLat: number, randomLng: number;
    //
    //     let positions = [];
    //     for (let i = 0 ; i < 9; i++) {
    //         randomLat = Math.random() * (43.7399 - 43.7300) + 43.7300;
    //         randomLng = Math.random() * (-79.7600 - -79.7699) + -79.7699;
    //         positions.push([randomLat, randomLng]);
    //     }
    //     return positions;
    // }

    // this is an example that uses an Observable - much like
    // the call to your service. This works, and is called OnInit,
    // and when the button is pushed too.
    // showMarkersFromObservable() {
    //     Observable.of(this.getRandomMarkers()) // Think this as http call
    //         .subscribe( positions => {
    //             this.positions = positions;
    //         });
    // }

}
import {Component, OnInit} from "@angular/core";
import {Hub} from "../classes/hub";
import {HubService} from "../services/hub.service";
import {ActivatedRoute, Params} from "@angular/router";
import 'rxjs/add/operator/switchMap';


@Component({
	templateUrl: "./templates/hub-panel.html",
	selector: "hub-panel"
})

export class HubPanelComponent implements OnInit {

	hubs: Hub[] = [];
	constructor(private hubService: HubService, private activatedRoute: ActivatedRoute) {}

	getAllHubs(): void {
		this.activatedRoute.params
			.switchMap((params: Params) => this.hubService.getAllHubs())
			.subscribe(hubs => {
				hubs.map(hub => {
				});
				this.hubs = hubs;
			});
	}

	ngOnInit(): void {
		this.getAllHubs();
	}
}

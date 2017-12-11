import {Component, OnInit} from "@angular/core";
import {HubService} from "../services/hub.service";
import 'rxjs/add/operator/switchMap';
import {Hub} from "../classes/hub";
import {User} from "../classes/user";


@Component({
	templateUrl: "./templates/hub-panel.html",
	selector: "hub-panel"
})

export class HubPanelComponent implements OnInit {

	hub: Object[] = [];
	hubs: Object[] = [];

	constructor(private hubService: HubService) {
	}

	getAllHubs(): void {
		this.hubService.getAllHubs().subscribe(hubs => this.hubs = hubs);
	}

	ngOnInit(): void {
		this.getAllHubs();
	}

	getHub(hubId: string): void {
		this.hubService.getHub(hubId)
			.subscribe(hub => this.hub = hub);
	}
}

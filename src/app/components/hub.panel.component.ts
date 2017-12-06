import {Component, OnInit} from "@angular/core";
import {Hub} from "../classes/hub";
import {HubService} from "../services/hub.service";

@Component({
	templateUrl: "./templates/hub-panel.html",
	selector: "hub-panel"
})

export class HubPanelComponent implements OnInit {

	hubs: Hub[] = [];

	constructor(private hubService: HubService) {}

	getAllHubs(): void {
		this.hubService.getAllHubs()
			.subscribe(hubs => this.hubs = hubs);
	}

	ngOnInit(): void {
		this.getAllHubs();
	}
}

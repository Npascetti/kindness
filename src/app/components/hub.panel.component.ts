import {Component, OnInit} from "@angular/core";
import {HubService} from "../services/hub.service";
import 'rxjs/add/operator/switchMap';


@Component({
	templateUrl: "./templates/hub-panel.html",
	selector: "hub-panel"
})

export class HubPanelComponent implements OnInit {

	hubs: Object[] = [];

	constructor(private hubService: HubService) {
	}

	getAllHubs(): void {
		this.hubService.getAllHubs().subscribe(hubs => this.hubs = hubs);
	}

	ngOnInit(): void {
		this.getAllHubs();
	}
}

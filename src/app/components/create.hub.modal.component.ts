import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../classes/status";
import {HubService} from "../services/hub.service";
import {Hub} from "../classes/hub";

@Component({
	templateUrl: "./templates/create-hub-modal.html",
	selector: "create-hub"
})

export class CreateHubModalComponent implements OnInit{
	createHubForm: FormGroup;
	status: Status = null;

	constructor(private formBuilder: FormBuilder, private hubService: HubService) {}

	ngOnInit(): void {
		this.createHubForm = this.formBuilder.group({
			hubLocation: ["", [Validators.maxLength(264), Validators.required]],
			hubName: ["", [Validators.maxLength(128), Validators.required]]
		});
	}

	createHub(): void {
		let hub = new Hub(null, null, this.createHubForm.value.hubLocation, this.createHubForm.value.hubName);
		this.hubService.createHub(hub).subscribe(status => {
			this.status = status;
			if(this.status.status === 200) {
				this.createHubForm.reset();
			}
		});
	}
}

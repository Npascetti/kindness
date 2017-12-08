import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../classes/status";
import {HubService} from "../services/hub.service";
import {Hub} from "../classes/hub";
import {JwtHelperService} from "@auth0/angular-jwt";


@Component({
	templateUrl: "./templates/create-hub-modal.html",
	selector: "create-hub"
})

export class CreateHubModalComponent implements OnInit{
	createHubForm: FormGroup;
	status: Status = null;
	authObj: any = {};


	constructor(private formBuilder: FormBuilder, private hubService: HubService, private jwtHelperService: JwtHelperService) {}

	ngOnInit(): void {
		this.createHubForm = this.formBuilder.group({
			hubLocation: ["", [Validators.maxLength(264), Validators.required]],
			hubName: ["", [Validators.maxLength(128), Validators.required]]
		});
	}

	createHub(): void {
		this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
		let hub = new Hub(null, this.authObj.auth["userId"], this.createHubForm.value.hubLocation, this.createHubForm.value.hubName);
		this.hubService.createHub(hub).subscribe(status => {
			this.status = status;
			if(this.status.status === 200) {
				this.createHubForm.reset();
			}
		});
	}
}

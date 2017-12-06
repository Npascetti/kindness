import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../classes/status";
import {Router} from "@angular/router";
import {UserService} from "../services/user.service";


@Component({
	templateUrl: "./templates/edit-profile-modal.html",
	selector: "edit-profile"
})
export class EditProfileComponent{
	editProfileForm: FormGroup;
	status: Status = null;

	constructor(private formBuilder: FormBuilder, private userService: UserService) {}

	ngOnInit(): void {
		this.editProfileForm = this.formBuilder.group({

		})
	}
}
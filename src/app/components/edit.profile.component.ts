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
			userName: ["", [Validators.maxLength(128), Validators.required]],
			firstName: ["", [Validators.maxLength(64), Validators.required]],
			lastName: ["", [Validators.maxLength(64), Validators.required]],
			email: ["", [Validators.maxLength(255), Validators.required]],
			password: ["", [Validators.maxLength(48), Validators.required]],
			passwordConfirm: ["", [Validators.maxLength(48), Validators.required]]

		});
		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.editProfileForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.editProfileForm[field] = values[field];
			}
		});
	}

	editUser() : void {
		this.userService.editUser(this.)
	}
}
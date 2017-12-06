import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../classes/status";
import {User} from "../classes/user";
import {UserService} from "../services/user.service";
import {ActivatedRoute, Params} from "@angular/router";



@Component({
	templateUrl: "./templates/edit-profile-modal.html",
	selector: "edit-profile"
})
export class EditProfileComponent implements OnInit{
	editProfileForm: FormGroup;
	status: Status = null;
	user: User = new User(null, null, null, null, null, null, null, null, null);

	constructor(private formBuilder: FormBuilder, private userService: UserService, private route: ActivatedRoute) {}

	ngOnInit(): void {
		this.route.params.forEach((params : Params) => {
			let userId = params["userId"];
			this.userService.getUser(userId)
				.subscribe(user => {
					this.user = user;
					this.editProfileForm.patchValue(user);
				});
		});
		this.editProfileForm = this.formBuilder.group({
			userName: ["", [Validators.maxLength(128), Validators.required]],
			firstName: ["", [Validators.maxLength(64), Validators.required]],
			lastName: ["", [Validators.maxLength(64), Validators.required]],
			email: ["", [Validators.maxLength(255), Validators.required]],
			password: ["", [Validators.maxLength(48), Validators.required]],
			passwordConfirm: ["", [Validators.maxLength(48), Validators.required]]

		});
		this.editUser();
	}

	editUser() : void {
		this.editProfileForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.editProfileForm[field] = values[field];
			}
		});
	}
}
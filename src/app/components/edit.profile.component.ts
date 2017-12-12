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
export class EditProfileComponent implements OnInit {
	editProfileForm: FormGroup;
	status: Status = null;
	userData: any;
	editUser: User = new User(null, null, null, null, null, null, null, null, null);

	constructor(private formBuilder: FormBuilder, private userService: UserService, private route: ActivatedRoute) {
	}

	ngOnInit(): void {
		this.getUser(this.route.snapshot.params["id"]);
		this.editProfileForm = this.formBuilder.group({
			userName: ["", [Validators.maxLength(128)]],
			firstName: ["", [Validators.maxLength(64)]],
			lastName: ["", [Validators.maxLength(64)]],
			email: ["", [Validators.maxLength(255)]],
			bio: ["", [Validators.maxLength(3000)]],
		});
	}

	getUser(id: string): void {
		this.userService.getUser(id)
			.subscribe((reply: any) => {
				this.userData = reply;
				this.editUser = new User(this.userData.userId, this.userData.userBio, this.userData.userEmail,
					this.userData.userFirstName, this.userData.userImage, this.userData.userLastName,
					null, null, this.userData.userUserName);
				this.editProfileForm.patchValue(this.editUser);
			});
	}

	applyFormChanges(): void {
		this.editProfileForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.editUser[field] = values[field];
			}
		});
	}

	editProfile(): void {
		this.applyFormChanges();
		this.userService.editUser(this.editUser)
			.subscribe(status => this.status = status);
		console.log(this.editUser);
	}
}
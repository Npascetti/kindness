import {Component} from "@angular/core";
import {Status} from "../classes/status";


@Component({
	templateUrl: "./templates/edit-profile-modal.html",
	selector: "edit-profile"
})
export class EditProfileComponent{
	@ViewChild("editProfile") editProfile: any;

	editProfile: EditProfile = new EditProfile(null, null, null, null, null, null);
	status: Status = null;



}
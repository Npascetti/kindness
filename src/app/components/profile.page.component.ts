import {Component, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../classes/user";
import {UserService} from "../services/user.service";
import {ActivatedRoute} from "@angular/router";

//enable Jquery $ alias
declare const $: any;

@Component({
	templateUrl: "./templates/profile-page.html"
})

export class ProfilePageComponent implements OnInit{

	user:User = new User(null, null, null, null, null, null, null, null, null) ;

	userData: any;

	authObj: any = {};

	constructor (
		private jwtHelperService: JwtHelperService,
		private userService: UserService,
		private route: ActivatedRoute
	){}

	ngOnInit() : void {
			this.getUser(this.route.snapshot.params["id"]);
	}

	getUser(id: number): void {
			this.userService.getUser(id)
				.subscribe((reply:any) => {
				this.userData = reply;
				this.user = new User(this.userData.userId, this.userData.userBio, this.userData.userEmail, this.userData.userFirstName, this.userData.userImage, this.userData.userLastName, null, null, this.userData.userUserName);
					}
				);
	}

	getJwtProfileId() : any {
			this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
	}
}


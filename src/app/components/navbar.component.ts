import {Component, OnChanges, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../classes/user";
import {SignInService} from "../services/sign.in.service";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
//enable Jquery $ alias
declare const $: any;

@Component({
	templateUrl: "./templates/navbar.html",
	selector: "navbar"
})

export class NavbarComponent implements OnInit{
	token: string = localStorage.getItem("jwt-token");

	user:User = new User(null, null, null, null, null, null, null, null, null) ;


	authObj: any = {};


	constructor (
		private jwtHelperService: JwtHelperService, private signInService: SignInService, private cookieService: CookieService, private router: Router
	){}

	ngOnInit() : void {
		this.getJwtProfileId();
	}

	getJwtProfileId() : any {
			//console.log(this.token);
		if(this.token){

			let tokenExpired = this.jwtHelperService.isTokenExpired(this.token);
			 if(tokenExpired === false){
			 	this.authObj = this.jwtHelperService.decodeToken(this.token);
			 	}
			//console.log(tokenExpired);

		}
	}

	// signOut() : void {
	// 		this.signInService.signOut().subscribe(status => {
	// 			this.status = status;
	// 			if(status.status === 200) {
	// 				this.cookieService.deleteAll();
	// 				localStorage.clear();
	// 				this.router.navigate([""]);
	// 				location.reload();
	// 				console.log("goodbye");
	// 			}
	// 		});
	// }
}


import { JwtHelperService } from '@auth0/angular-jwt';
import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";


@Injectable()

export class AuthService {


	private token : string = localStorage.getItem("jwt-token");



	constructor(private jwtHelperService: JwtHelperService, private http: HttpClient) {}

	//token : string = this.jwtHelperService.tokenGetter();
	loggedIn() {



		if (this.token) {
			return false;
		}



		return !tokenExpired
	}

	decodeJwt() : any {
		let isLoggedIn : boolean = this.loggedIn();

		if (!isLoggedIn) {
			return false;
		}
		const authObject = this.jwtHelperService.decodeToken(this.token);

		console.log(authObject);

		return authObject;
	}

}

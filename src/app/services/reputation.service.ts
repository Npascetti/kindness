import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Reputation} from "../classes/reputation";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class ReputationService {

	constructor(protected http : HttpClient ) {

	}

	//define the API endpoint
	private reputationUrl = "/api/reputation/";


	//call the reputation API and create a new reputation
	createReputation(reputation : Reputation) : Observable<Status> {
		return (this.http.post<Status>(this.reputationUrl, reputation));
	}

	// call to the reputation API and delete the reputation in question
	deleteReputation(reputationId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.reputationUrl + reputationId));
	}

	// call to the reputation API and edit the reputation in question
	editReputation(reputation : Reputation) : Observable<Status> {
		return(this.http.put<Status>(this.reputationUrl + reputation.reputationId, reputation));
	}

	//grabs a  reputation based on its id
	getReputation(reputationId : string) : Observable <Reputation> {
		return (this.http.get<Reputation>(this.reputationUrl+ reputationId))
	}

	//grabs a reputation based on its reputation id
	//TODO ask george about getting this as an int
	getReputationByReputationHubId (reputationHubId : string) : Observable<Reputation> {
		return(this.http.get<Reputation>(this.reputationUrl + reputationHubId))
	}

	getRepuationByReputationLevelId(reputationLevelId : string) : Observable<Reputation[]> {
		return(this.http.get<Reputation[]>(this.reputationUrl + reputationLevelId))
	}

	getReputationByReputationUserId(reputationUserId : string) : Observable<Reputation[]> {
		return(this.http.get<Reputation[]>(this.reputationUrl + reputationUserId))
	}
}
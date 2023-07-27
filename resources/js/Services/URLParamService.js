class UrlParamService {

    serachParams = new URLSearchParams(window.location.search);


    hasParam(param){
        const urlParams = this.serachParams;
        return urlParams.has(param);
    }
    
    getParamList(){
        const urlParams = this.serachParams;
        const params = {};

        for (const [param, value] of urlParams.entries()) {
            params[param] = value;
        }

        return params;
    }
    
    
    getParam(param){
        const urlParams = this.serachParams;
        return urlParams.get(param);
    }

}


export { UrlParamService }; 
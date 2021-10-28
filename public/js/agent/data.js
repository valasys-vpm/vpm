
let URL = $('meta[name="base-path"]').attr('content');
let DATA_TABLE;

$(function (){

    DATA_TABLE = $('#table-data').DataTable({
        "lengthMenu": [ [200,100,50,25,10,-1], [200,100,50,25,10,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": URL + '/agent/data/get-agent-data',
            data: {
                filters: function (){
                    let obj = {
                    };
                    localStorage.setItem("filters", JSON.stringify(obj));
                    return JSON.stringify(obj);
                },
                ca_agent_id: $('meta[name="ca-agent-id"]').attr('content')
            },
            error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
        },
        "columns": [
            {
                data: 'first_name'
            },
            {
                data: 'last_name'
            },
            {
                data: 'company_name'
            },
            {
                data: 'email_address'
            },
            {
                data: 'specific_title'
            },
            {
                data: 'job_level'
            },
            {
                data: 'job_role'
            },
            {
                data: 'phone_number'
            },
            {
                data: 'address_1'
            },
            {
                data: 'address_2'
            },
            {
                data: 'city'
            },
            {
                data: 'state'
            },
            {
                data: 'zipcode'
            },
            {
                data: 'country'
            },
            {
                data: 'employee_size'
            },
            {
                data: 'revenue'
            },
            {
                data: 'company_domain'
            },
            {
                render: function (data, type, row) {
                    if(row.website.length) {
                        if(row.website.includes('http')) {
                            return '<a href="'+ row.website +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        } else {
                            return '<a href="https://'+ row.website +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        }
                    } else {
                        return 'Not Available';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    if(row.company_linkedin_url.length) {
                        if(row.company_linkedin_url.includes('http')) {
                            return '<a href="'+ row.company_linkedin_url +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        } else {
                            return '<a href="https://'+ row.company_linkedin_url +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        }
                    } else {
                        return 'Not Available';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    if(row.linkedin_profile_link.length) {
                        if(row.linkedin_profile_link.includes('http')) {
                            return '<a href="'+ row.linkedin_profile_link +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        } else {
                            return '<a href="https://'+ row.linkedin_profile_link +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        }
                    } else {
                        return 'Not Available';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    if(row.linkedin_profile_sn_link.length) {
                        if(row.linkedin_profile_sn_link.includes('http')) {
                            return '<a href="'+ row.linkedin_profile_sn_link +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        } else {
                            return '<a href="https://'+ row.linkedin_profile_sn_link +'" target="_blank">Redirect <i class="fas fa-external-link-alt"></i></a>';
                        }
                    } else {
                        return 'Not Available';
                    }
                }
            }

        ],
    });

});

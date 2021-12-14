/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let LEAD_TABLE;
let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

$(function (){

    LEAD_TABLE = $('#table-leads').DataTable({
        "lengthMenu": [ [500,400,300,200,100,-1], [500,400,300,200,100,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": URL + '/team-leader/lead/get-agent-leads',
            data: {
                filters: function (){
                    let obj = {
                    };
                    localStorage.setItem("filters", JSON.stringify(obj));
                    return JSON.stringify(obj);
                },
                ca_ratl_id: $('meta[name="ca-ratl-id"]').attr('content')
            },
            error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
        },
        "columns": [
            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';
                    html += '<a href="'+URL+'/team-leader/lead/edit/'+btoa(row.id)+'" class="btn btn-outline-secondary btn-rounded btn-sm" title="Edit lead details" style="padding: 2px 5px;"><i class="feather icon-edit mr-0" ></i></a>';
                    html += '<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm" onclick="rejectLead(\''+btoa(row.id)+'\')" title="Reject lead" style="padding: 2px 5px;"><i class="feather icon-x mr-0" ></i></a>';
                    return html;
                }
            },
            {
                render: function (data, type, row) {
                    return row.agent.full_name;
                }
            },
            {
                render: function (data, type, row) {
                    return moment(row.created_at).format('DD-MMM-YYYY');
                }
            },
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
                data: 'industry'
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
                data: 'website'
            },
            {
                data: 'company_linkedin_url'
            },
            {
                data: 'linkedin_profile_link'
            },
            {
                data: 'linkedin_profile_sn_link'
            },
            {
                data: 'comment'
            }
        ],
        "createdRow": function(row, data, dataIndex){
            switch (parseInt(data.status)) {
                case 0:
                    $(row).addClass('border-cancelled');
                    break;
            }
        }
    });

    $('#form-reject-lead-submit').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: URL + '/team-leader/lead/reject/' + $('#form-reject-lead').find('input[name="agent_lead_id"]').val(),
            data: $('#form-reject-lead').serialize(),
            async : true,
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', 'Agent\'s lead rejected successfully');
                    $('#modal-reject-lead').modal('hide');
                    LEAD_TABLE.ajax.reload();
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    });

});

function rejectLead(_agent_lead_id) {
    if(confirm('Are you sure to reject this lead?')) {
        $('#form-reject-lead').find('input[name="agent_lead_id"]').val(_agent_lead_id)
        $('#modal-reject-lead').modal('show');
    }
}

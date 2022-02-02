@extends('layout.principal')

@section('title')
<i class="fa fa-map"></i> Mapa de Vendas - 2021
@append 

@section('conteudo')



@section('title')
<i class="fa fa-plus"></i>Power BI
@append 

@section('css')

@stop

@section('conteudo')

<div id="reportContainer" style="width: 80%; height: 800px;"></div>  

<script>  
    window.onload = function () {  
  
        var models = window['powerbi-client'].models;  

        var embedConfiguration = {  
            type: 'report',  
            id: '5a860130-9256-4ecf-82ab-678366405a5f',  
            embedUrl: 'https://app.powerbi.com/reportEmbed?reportId=5a860130-9256-4ecf-82ab-678366405a5f&groupId=5680ca39-e2d6-48df-9c81-321589d63b7b&w=2&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly9XQUJJLUJSQVpJTC1TT1VUSC1CLVBSSU1BUlktcmVkaXJlY3QuYW5hbHlzaXMud2luZG93cy5uZXQiLCJlbWJlZEZlYXR1cmVzIjp7Im1vZGVybkVtYmVkIjp0cnVlLCJhbmd1bGFyT25seVJlcG9ydEVtYmVkIjp0cnVlLCJjZXJ0aWZpZWRUZWxlbWV0cnlFbWJlZCI6dHJ1ZSwidXNhZ2VNZXRyaWNzVk5leHQiOnRydWUsInNraXBab25lUGF0Y2giOnRydWV9fQ%3d%3d',  
            tokenType: models.TokenType.Embed,  
            accessToken: 'H4sIAAAAAAAEAB2Tx66DVgAF_-VtiWR6iZQFHUzvZXdNs7n0YkqUf89L9rM5czR__7jg6kZQ_vz5U8zh1OcwC1qSyHdsRV53krK8vTBuENbI5ao0ZRZlYzmRUYhhs23cEqWLH1K0p9t7L2eHsZ8cbJqsFcxePJO9gfoUgkFouzNf-6GlixnuHM4DKUtgRL_q3FOQJ6a7aFhfHz_pPUWNEePZ3ljVYZSibji0rlMTQKnkg143ijBf1eS55C42OS5qHYfMUSdkB9UeLN01Njq4knqjWGIMzK2Z7cxRhTjrTSIHEqC-XD8f8WmQSR07LqXOYdLrCmu-WflTsz1Zc-nuDgwXGuMJeKlk2n7K3EPnZ6u800wVdKV20WTlhSEsWem7IuyXkeQ33pl6tCR5J3dDiyBYYHqoOhh7qdrqKLFwx90NQcqTzjfq7qvkK7ApZH8H9FLhjJrD-pm56_in-ZyZuouUrjhv98FAVD7S6DW45H25c0Y8XvSuVkC5k9MjQfs9L320bgTUVqqwBqU_br6uyq52MYr_As13V9OKnWxpQU4m5DOaJZ_FbjKCjo2IfGTRaR0gcA3V1rs3H5E5-_qyuGl6StPLL-WpH0shaVurJQfeFBOHZW-4WNu0jxGhf2ZKe8VxDLSLfnxtkQqZ8KDKoP-9iLHHPX7j_v0ZJ_js5Jynxr6qXKHIJ0mdhnJ6Ci_DLsaAK132PpTJxfjqog5KhQPoqZwHm-gjPMZDO2S0ruYNeI9J3BdC9QbcuSyi51eBAJ-q2Kg9o1idr7YiwDpIaBF6smGx23DE3vjw-nh12MJXWIWNIE2hBTGttb1g_riMTXv3y-IqyKdb5AcojpzPA309qWT-uq8zOB9bnPBiqkuQHvHhIjZm5Wyz3k8lI2UXoM7xeEU7UT_cy9d-_vgRl2vaRqO6fnNaTVAAzlgGaYmWKfCFlYNiYReeOvWgMmJ_qpWNYkRHy1LGTNHEMrh4w8nxA07p0zSm8v7U4xeSuwAYhsxbuReqjO7z_TkgZcSHm8ZbUJZBiBLrAruyTCl8PK3uUsmlh0Rmh8JMGAPGqqE7phqaWtGqRPgKWncH7-lASFdETk-URkQPQQQzWzkvIOW2NImol7cAPZUmZChMUmNWW369IkmS8VlIH-1VJtcLtWgHwTDwGpmY9uQQPTGftAba7USZZJgnKGkSAE_PnqyCnCEuvPMHTuj9-6pW1ukHlqnCIXrrCBFUsJhMgwh0S_vuixXyUakZD9fUbM3sqApocsApb145_vrrP83X9K4WPf613GmQ03vRfg7OAxK0YCUXIvD_U8GnGcC2L9UvpvhRFDrCcA88a2_q-grPeRUXUHzP7xisDLGqZ2EG5NVNHI7VMksJTndoPGJd8OqTckprOf3UKHRFbxwQdlWznlhJ81aHdbQQVUG3kk1tgbgyRLBnyfxAf_KOCp_KFAkZzEfHO_qtYafLteVFQZldCjfcU-uXuMqDgnVhq74ocS3JUlYzdO6KOTdThPIqHz0bLUX32vXYTr0NJyoL0TodxIJw5SsDxxL3HP2wZRVXHU_C2n2N5A9Sr_ZjTDv80HCysfbnbdM030QXDwjqO9cTNddw2PbOHK9efOa37GxxfzpeF4F4eyPfm7bAe9yBLz78wasQW6N5-je9XKAtsyuMvvpP8z__AtPhbdlCBgAA.eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly9XQUJJLUJSQVpJTC1TT1VUSC1CLVBSSU1BUlktcmVkaXJlY3QuYW5hbHlzaXMud2luZG93cy5uZXQiLCJlbWJlZEZlYXR1cmVzIjp7Im1vZGVybkVtYmVkIjpmYWxzZX19',
        };  

        var $reportContainer = $('#reportContainer');  
        var report = powerbi.embed($reportContainer.get(0), embedConfiguration);  

    };  
</script>

@stop

@section('js')

    <script type="text/javascript" language="javascript" src="https://rawgit.com/Microsoft/PowerBI-JavaScript/master/dist/powerbi.min.js"></script> </script>  

@stop

<!-- 
<script>
	export interface IEmbedConfiguration {
    accessToken: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik1yNS1BVWliZkJpaTdOZDFqQmViYXhib1hXMCIsImtpZCI6Ik1yNS1BVWliZkJpaTdOZDFqQmViYXhib1hXMCJ9.eyJhdWQiOiJodHRwczovL2FuYWx5c2lzLndpbmRvd3MubmV0L3Bvd2VyYmkvYXBpIiwiaXNzIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvNWUwZDk4YjItNDJmZC00ZmU5LTk5Y2ItNjdlNTI1ODZiYzdkLyIsImlhdCI6MTY0MzI4ODQ5MCwibmJmIjoxNjQzMjg4NDkwLCJleHAiOjE2NDMyOTI2NTIsImFjY3QiOjAsImFjciI6IjEiLCJhaW8iOiJBVFFBeS84VEFBQUF4R09LdGtMOTBmSVo5S003Q3ZodllBbTgwY2h0aGFuWVIwbUNhK2VmV2xvSW1lRnZLSThWZ3RqTzJjSEJPN3FLIiwiYW1yIjpbInB3ZCJdLCJhcHBpZCI6IjIzZDhmNmJkLTFlYjAtNGNjMi1hMDhjLTdiZjUyNWM2N2JjZCIsImFwcGlkYWNyIjoiMCIsImZhbWlseV9uYW1lIjoiMDAwODQiLCJnaXZlbl9uYW1lIjoiS0VOU1AiLCJpcGFkZHIiOiIxNzkuMTkxLjc3LjI2IiwibmFtZSI6IktFTlNQIDAwMDg0Iiwib2lkIjoiM2U2ZjhlZjctZDU4Ny00ZmYwLTk0ZDYtMDEwMWU0YjhiZmQwIiwicHVpZCI6IjEwMDMyMDAwRDY0RTE3M0MiLCJyaCI6IjAuQVNVQXNwZ05YdjFDNlUtWnkyZmxKWWE4ZmIzMjJDT3dIc0pNb0l4NzlTWEdlODBsQUtJLiIsInNjcCI6IkFwcC5SZWFkLkFsbCBDYXBhY2l0eS5SZWFkLkFsbCBDYXBhY2l0eS5SZWFkV3JpdGUuQWxsIENvbnRlbnQuQ3JlYXRlIERhc2hib2FyZC5SZWFkLkFsbCBEYXNoYm9hcmQuUmVhZFdyaXRlLkFsbCBEYXRhZmxvdy5SZWFkLkFsbCBEYXRhZmxvdy5SZWFkV3JpdGUuQWxsIERhdGFzZXQuUmVhZC5BbGwgRGF0YXNldC5SZWFkV3JpdGUuQWxsIEdhdGV3YXkuUmVhZC5BbGwgR2F0ZXdheS5SZWFkV3JpdGUuQWxsIFBpcGVsaW5lLkRlcGxveSBQaXBlbGluZS5SZWFkLkFsbCBQaXBlbGluZS5SZWFkV3JpdGUuQWxsIFJlcG9ydC5SZWFkLkFsbCBSZXBvcnQuUmVhZFdyaXRlLkFsbCBTdG9yYWdlQWNjb3VudC5SZWFkLkFsbCBTdG9yYWdlQWNjb3VudC5SZWFkV3JpdGUuQWxsIFRlbmFudC5SZWFkLkFsbCBUZW5hbnQuUmVhZFdyaXRlLkFsbCBVc2VyU3RhdGUuUmVhZFdyaXRlLkFsbCBXb3Jrc3BhY2UuUmVhZC5BbGwgV29ya3NwYWNlLlJlYWRXcml0ZS5BbGwiLCJzaWduaW5fc3RhdGUiOlsia21zaSJdLCJzdWIiOiJQN1A1Z01UbUExQnU5WDV2QVQxTXhmMGZlSG94V3k3NV9zbjlzWXAwMl9jIiwidGlkIjoiNWUwZDk4YjItNDJmZC00ZmU5LTk5Y2ItNjdlNTI1ODZiYzdkIiwidW5pcXVlX25hbWUiOiJLRU5TUC0wMDA4NEBnb2V5ZXdlYXIub25taWNyb3NvZnQuY29tIiwidXBuIjoiS0VOU1AtMDAwODRAZ29leWV3ZWFyLm9ubWljcm9zb2Z0LmNvbSIsInV0aSI6IlA2b2pTTkJDbmt5OVRkQzNlUmxmQUEiLCJ2ZXIiOiIxLjAiLCJ3aWRzIjpbImE5ZWE4OTk2LTEyMmYtNGM3NC05NTIwLThlZGNkMTkyODI2YyIsImI3OWZiZjRkLTNlZjktNDY4OS04MTQzLTc2YjE5NGU4NTUwOSJdfQ.AgcJ_KA8IZP2q0NsfRZZkXBDoerXEQjPHMjTsAOexuAIpzPy8bdHrGq-W_9eSCnUVKpsvF5Ua7E-apOpTxyqyr-8Sd0PknjUeM9exWPGJAKEeVJuFzBVixLrMGtxJ4s9uefu753ZRhC7-QBbXnpYFOUCWydAmNQEHirwt2ykwPXHhN3IloKhBaRxlkKn-0rDhD4b3XDfU1Sh_01pZ2HuPJXE4rNMzjZosRoUHjL43xK4SsMCnxiLEN5KvO6rPDuSuAiAeSxQ0BDlO7bY9HraSDOePjOpE-7G0vPKeQU43sb1r4-4-Tggw84gjU2uNJ1QZM9MdC6dmYl4-B-lU_zMmw';
       uniqueId: '5e0d98b2-42fd-4fe9-99cb-67e52586bc7d';
    embedUrl?: 'https://api.powerbi.com/v1.0/myorg/groups/d54358f3-a496-4757-8bcf-0574555a54fb/reports';
    id?: 'd54358f3-a496-4757-8bcf-0574555a54fb';
     hostname?: string;
    settings?: IPaginatedReportSettings;
    tokenType?: TokenType;
    type?: 'report';
}
	 -->
	

// interface IReportLoadConfiguration {
//     accessToken: ;
//     bookmark?: models.IApplyBookmarkRequest;
//     contrastMode?: models.ContrastMode;
//     datasetBinding?: models.IDatasetBinding;
//     embedUrl?: 'https://app.powerbi.com/groups/me/apps/cc5b65a3-4787-4825-a2a5-e050188ecc3e/reports/d0c63a7f-5a7f-4df4-83d0-bea49f6a1b94/ReportSection0ca334eed4a2fd1711a7';
//     filters?: models.ReportLevelFilters[];
//     id: '2153590';
//     pageName?: "ReportSectiona271643cba2213c935be";
//     permissions?: models.Permissions.All;
//     settings?: models.IEmbedSettings;
//     slicers?: models.ISlicer[];
//     theme?: models.IReportTheme;
//     tokenType?: models.TokenType;
//     type: 'report';
//     viewMode?: models.ViewMode;
// }
</script>


<!-- <div >
 
<iframe title="Mapa de vendas - Página 1" width="1140" height="541.25" src="https://app.powerbi.com/reportEmbed?reportId=99e00dcd-be26-4346-891a-6c4fcbb340c9&autoAuth=true&ctid=5e0d98b2-42fd-4fe9-99cb-67e52586bc7d&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly93YWJpLWJyYXppbC1zb3V0aC1iLXByaW1hcnktcmVkaXJlY3QuYW5hbHlzaXMud2luZG93cy5uZXQvIn0%3D" frameborder="0" allowFullScreen="true"></iframe>-->
    </div>

-->
@stop 

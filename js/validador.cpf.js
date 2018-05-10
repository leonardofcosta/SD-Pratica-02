$(function() {
    $("#cpf").blur(function() {
        $.validator.addMethod("TestaCPF", function(value, element) {

            var Soma;
            var Resto;
            Soma = 0;

            value = value.replace("-","");
            value = value.replace(/\./g,"");

            // CPFs invalidos
            var invalidos = [
                '11111111111',
                '22222222222',
                '33333333333',
                '44444444444',
                '55555555555',
                '66666666666',
                '77777777777',
                '88888888888',
                '99999999999',
                '00000000000'
            ];
            for(i=0;i<invalidos.length;i++) {
                if( invalidos[i] == value) {
                    return false;
                }
            }

            for (i=1; i<=9; i++) Soma = Soma + parseInt(value.substring(i-1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(value.substring(9, 10)) ) return false;

            Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(value.substring(i-1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(value.substring(10, 11) ) ) return false;
            
            return true;

        });

    });

    $("#frm").validate({
        rules: {
            cpf:{
                required: true,
                TestaCPF: true
            }
        },
        
        messages: {
            cpf: {
                required: "Por favor informe o CPF.",
                TestaCPF: "Por favor informe um CPF válido!"
            }
        },

        errorElement: "div"
    });

 });
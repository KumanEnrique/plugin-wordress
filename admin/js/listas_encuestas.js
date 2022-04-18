jQuery(document).ready(function($){
    const btn = document.getElementById('add')
    const table = document.getElementById('dinamicos')
    let i = 1
    btn.addEventListener('click',(e)=>{
        e.preventDefault()
        const createRow = document.createElement('div')
        createRow.className = "row mb-1"
        createRow.innerHTML =  `
            <div class="col-lg-3">
                <p>Pregunta ${i}</p>
            </div>
            <div class="col-lg-3">
                <input type="text" name="name[]" class="form-control">
            </div>
            <div class="col-lg-3">
                <select name="type[]" id="type" class="form-control type-list">
                    <option value="1" >SI O NO</option>
                    <option value="2" selected >RANGO 0 - 5</option>
                </select>
            </div>
            <div class="col-lg-3">
                <button class="btn-danger">Eliminar</button>
            </div>
        `
        i++
        table.appendChild(createRow)
    })

    $(document).on('click',"a[data-id]",function(){
        let id = this.dataset.id
        const url = SolicitudesAjax.url
        $.ajax({
            type:"POST",
            url:url,
            data:{
                action:"peticionEliminar",
                nonce:SolicitudesAjax.saguridad,
                id:id
            },
            success:function(){
                location.reload()
                alert('datos borrados')
            }
        })
    })
});
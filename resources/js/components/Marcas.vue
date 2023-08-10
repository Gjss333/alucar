<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o Id da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>  
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="NomeHelp" texto-ajuda="Opcional. Informe o Nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                                </input-container-component> 
                            </div>
                        </div>
                    </template> 
            
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            :dados="marcas.data"
                            :visualizar="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaVisualizar' }"
                            :atualizar="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaAtualizar'}"
                            :remover="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaRemover' }"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                nome: {titulo: 'Nome', tipo: 'text'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Data de Criação', tipo: 'data'},
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end"  data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>
    </div>

    <!-- Inclusao de marca -->
    <modal-component id="modalMarca" titulo="adicionar marca">

        <template v-slot:alertas>
            <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component> 
            <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo ="Error ao Tentar Cadastrar a marca" v-if="transacaoStatus == 'Erro'"></alert-component> 
        </template>

        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o Nome da marca">
                    <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                </input-container-component>

            </div>
            <div class="form-group">
                <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="selecione uma imagem em formato png">
                    <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>

    <modal-component id="modalMarcaVisualizar" titulo="visualizar marca">
        <template v-slot:alertas></template>
        <template v-slot:conteudo >
       
            <input-container-component titulo="ID">
                <input type="text" class="form-control" :value="$store.state.item.id" disabled>
            </input-container-component>

            <input-container-component titulo="Nome">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
            </input-container-component>

            <div class="d-flex align-items-center">

                <input-container-component titulo="Imagem" >
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
            </input-container-component>
            </div>

            <input-container-component titulo="Data de Criação">
                <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
            </input-container-component>
        </template>
        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </template>

    </modal-component>

    <!-- remoção de marcas -->
    <modal-component id="modalMarcaRemover" titulo="remover marca">
        <template v-slot:alertas>
            <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo v-if="$store.state.transacaoStatus != 'sucesso'">
            
            <input-container-component titulo="ID">
                <input type="text" class="form-control" :value="$store.state.item.id" disabled>
            </input-container-component>

            <input-container-component titulo="Nome">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
            </input-container-component>
        </template>
        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-danger"  @click="remover()" v-if="$store.state.transacaoStatus != 'sucesso'">Remover</button>
        
        </template>

    </modal-component>

    <!-- modal de atualização -->
    <modal-component id="modalMarcaAtualizar" titulo="atualizar marca">

        <template v-slot:alertas>
            <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>

        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component titulo="Nome da Marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o Nome da marca">
                    <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da Marca" v-model="$store.state.item.nome">
                </input-container-component>

            </div>
            <div class="form-group">
                <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="selecione uma imagem em formato png">
                    <input type="file" class="form-control" id="atualizarImagem" aria-describedby="atualizar    ImagemHelp" placeholder="selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
        </template>
    </modal-component>
</template>

<script>
    export default{
        computed: {
                token(){
                    let token = document.cookie.split(';').find(indice => {
                        return indice.startsWith('token=')
                    })

                    token = token.split('=')
                    token = 'Bearer ' + token[1]//refatorar codigo para so fazer o return do token.

                    return token
                }
            },

        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: { data: [] },
                busca: { id: '', nome: '' },
                urlPaginacao: '',
                urlFiltro: ''
            }
        },
        methods: {
            atualizar(){
                let formData = new FormData();
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)

                if(this.arquivoImagem[0]){
                    formData.append('imagem', this.arquivoImagem[0])
                }

                let url = this.urlBase + '/' + this.$store.state.item.id

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.item.transacao.status = 'sucesso'
                        this.$store.state.item.transacao.mensagem = 'Registro de marca Atualizado com Sucesso'

                        atualizarImagem.value = ''
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.item.transacao.status = 'erro'
                        this.$store.state.item.transacao.mensagem = errors.response.data.message
                        this.$store.state.item.transacao.dados = errors.response.data.errors
                    })
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro')

                if(!confirmacao){
                    return 'false'
                }

                let formData = new FormData();
                formData.append('_method', 'delete')

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                let url = this.urlBase + '/' + this.$store.state.item.id

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.item.transacao.status = 'sucesso'
                        this.$store.state.item.transacao.mensagem = response
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.item.transacao.status = 'erro'
                        this.$store.state.item.transacao.mensagem = errors.response.data.error
                    })
                    
            },
            pesquisar(){
                // console.log(this.busca)

                let filtro = ''

                for(let chave in this.busca){

                    if(this.busca[chave]){

                        if(filtro != ''){
                            filtro += ';'
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }

                }
                if(filtro != ''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro+'%'
                }else{
                    this.urlFiltro = ''
                }
                this.carregarLista()
            },
            paginacao(l){
                if(l.url){

                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarLista()
                }
            },

            carregarLista(){
                
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                axios.get(url, config)
                    .then(response => {
                        this.marcas = response.data
                        // console.log(response.data)
                        
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },

            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },

            salvar(){
                console.log(this.nomeMarca, this.arquivoImagem[0])

                let formData = new FormData();
                formData.append('nome', this.nomeMarca) 
                formData.append('imagem', this.arquivoImagem[0])
                
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        // sempre mudar o token
                        'Authorization': this.token

                    }
                }

                axios.post(this.urlBase, formData, config )
                    .then(response => {
                        this.transacaoStatus = 'Adicionado'
                        this.transacaoStatus = {
                            mensagem:'ID do registro ' + response.data.id 
                        } 
                    
                        
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'Erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message ,
                            dados: errors.response.data.errors   
                        }
                        console.log(errors.response.data.message)
                    })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>

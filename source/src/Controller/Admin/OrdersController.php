<?php
namespace App\Controller\Admin;


use App\Controller\Admin\AdminController;
use Cake\ORM\TableRegistry;
use App\Model\Entity\OrdersProduct;



/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 *
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index(){
        $this->paginate = [
            'contain' => ['Users'],
            'limit'  => 50,
        ];
        $orders = $this->paginate($this->Orders);
        //pr($orders) ;die;
        $this->set(compact('orders'));
    }

    /**
     * View method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
// code cũ
    // public function view($id = null)
    // {
    //     $order = $this->Orders->get($id, [
    //         'contain' => ['Users', 'Products']
    //     ]);

    //     $this->set('order', $order);
    // }

    // public function index()
    // {
    //     $this->paginate = [
    //         'contain' => ['Orders']
    //     ];
        
    //     //pr ($this->Orders); die();
    //     //$ordersProducts = $this->paginate($this->Orders->OrdersProducts);

    //     $this->set(compact('ordersProducts'));
    // }
//   public function initialize()
//     {
//          parent::initialize();
//         $query = TableRegistry::get('OrdersProducts');           
// }

    public function view($id = null)
    {
        // Orders ở đây là tìm đến Modal. Là entity hay table. pr thì ra được App\Model\Table\OrdersTable Object gồm key và value
        $order = $this->Orders->get($id, [  
            'contain' => ['OrdersProducts', 'Users']
        ]);// trỏ đến order có = $id

        //dd($order);die();
        $this->set(compact('order'));

        //$this->set(compact('order'));
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $order = $this->Orders->newEntity();
        if ($this->request->is('post')) {
            $order = $this->Orders->patchEntity($order, $this->request->getData());
            //pr( $order); die();
            if ($this->Orders->save($order)) {
                $this->Flash->success(__('The order has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The order could not be saved. Please, try again.'));
        }
        $users = $this->Orders->Users->find('list', ['limit' => 200]);
        $products = $this->Orders->Products->find('list', ['limit' => 200]);
        $this->set(compact('order', 'users'));
        //$this->set(compact('order', 'products'));
        //$this->set(compact('order', 'users'));
        //$this->set(compact('order', 'users'));
        $this->set(compact('order', 'users', 'products'));
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editorder($id = null)
    {
        $order = $this->Orders->get($id, [
            'contain' => ['Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $order = $this->Orders->patchEntity($order, $this->request->getData());
            if ($this->Orders->save($order)) {
                $this->Flash->success(__('The order has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The order could not be saved. Please, try again.'));
        }
        $users = $this->Orders->Users->find('list', ['limit' => 200]);
        $products = $this->Orders->Products->find('list', ['limit' => 200]);
        //$this->set(compact('order', 'users', 'products'));
        $this->set(compact('order', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $order = $this->Orders->get($id);
        if ($this->Orders->delete($order)) {
            $this->Flash->success(__('The order has been deleted.'));
        } else {
            $this->Flash->error(__('The order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function edit($id=null){
        if ($this->request->is(['patch', 'post', 'put'])) {
            $order=$this->Orders->get($id);
            $status=$this->request->data['statuss'];
            $order->status=$status;
            $this->Orders->save($order);
            $this->Flash->success(__('The order has been updated.'));
            $this->response->body(true);
            return $this->response;
        }
    }

}

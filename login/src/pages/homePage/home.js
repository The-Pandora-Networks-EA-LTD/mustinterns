import { useState } from 'react';
import axios from 'axios';

const Home = () => {
    const [data, setData] = useState([]);
    
    const changeState = () =>{
    axios.get('http://localhost/mustLoginBackend/users')
        .then(function (response) {
            setData(response?.data?.data)
            console.table(data)
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
    }
    return ( 
        <>
        <button className="btn btn-primary m-5" onClick={changeState}>Click Me</button>
        { data?.map((x) => (

            <p className="container m-5" key={x.id}>
                <span>{x.id}</span>
                <span>{x.usename}</span>
                <span>{x.password}</span>
                <span>{x.status}</span>
                <span>{x.timestamp}</span>
            </p>

            ))
        }
        
            
        </>
     );
}
 
export default Home;
// Ensure web3 is available. This could be injected by MetaMask or another Ethereum wallet.
(function ($) {
    $(document).ready(function () {
        let contractAdress = myplugin_params.contractAddress
        if (typeof window.ethereum !== 'undefined') {
            // Initialize web3 using the injected provider from the browser (e.g., MetaMask)
            const web3 = new Web3(window.ethereum);
            $(document).on('click', '#unlockContentButton', async function (e) {
                e.preventDefault();
                let base64Data = '123456'
                if (window.ethereum) {
                    try {
                        const web3 = new Web3(window.ethereum);
                        await window.ethereum.enable();  // Request access to account
                        const accounts = await web3.eth.getAccounts();

                        // Get the current network ID
                        const networkId = await web3.eth.net.getId();

                        // Define the ID for the Mode network (replace with actual ID)
                        const modeNetworkId = '34443';

                        if (networkId.toString() !== modeNetworkId) {
                             alert("Not connected to the Mode network. Please switch networks in your wallet.");
                            throw new Error("Not connected to the Mode network. Please switch networks in your wallet.");
                        }


                        // Define the contract ABI
                        const contractABI = [
                            // Add the ABI for the unlockPost function
                            {
                                "constant": false,
                                "inputs": [
                                    {
                                        "name": "_base64Data",
                                        "type": "string"
                                    }
                                ],
                                "name": "unlockPost",
                                "outputs": [],
                                "payable": false,
                                "stateMutability": "nonpayable",
                                "type": "function"
                            }
                        ];
                        if (accounts.length === 0) {
                            throw new Error("No accounts available to send the transaction.");
                        }

                        const contract = new web3.eth.Contract(contractABI, contractAdress);
                        const gasEstimate = await contract.methods.unlockPost(base64Data).estimateGas({ from: accounts[0] });

                        const response = await contract.methods.unlockPost(base64Data).send({ from: accounts[0], gas: gasEstimate });
                        console.log('Transaction response:', response);

                        // After transaction logic here...
                        document.getElementById('hiddenContent').style.display = 'block'; // Unhide the content.
                        document.getElementById('unlockContentButton').style.display = 'none'; // Unhide the content.
                        document.getElementById('warningTextWrapper').style.display = 'none'; 
                    } catch (error) {
                        console.error('Could not initiate Ethereum transaction', error);
                    }
                } else {
                    console.error('No Ethereum provider detected. Please install MetaMask or another wallet.');
                }
            })
        } else {
            console.error('No Ethereum provider detected. Please install MetaMask or another wallet.');
        }

    });
})(jQuery);